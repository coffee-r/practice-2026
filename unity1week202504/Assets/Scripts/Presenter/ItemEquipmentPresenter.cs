using System.Collections.Generic;
using System.Linq;
using Cysharp.Threading.Tasks;
using R3;
using R3.Triggers;
using TMPro;
using UnityEngine;
using UnityEngine.UI;

public class ItemEquipmentPresenter : MonoBehaviour
{
    [SerializeField] GameObject EquipmentItemPrefab;
    [SerializeField] Transform EquipmentItemParent;
    [SerializeField] Button closeButton;
    List<ItemData> EquipmentableItems;
    List<ItemData> EquipedItems;
    int CurrentEquipedItemCount;
    SceneContext sceneContext;
    AudioManager audioManager;

    // Start is called once before the first execution of Update after the MonoBehaviour is created
    void Start()
    {
        sceneContext = ServiceLocator.Instance.Resolve<SceneContext>();
        audioManager = ServiceLocator.Instance.Resolve<AudioManager>();
        CurrentEquipedItemCount = ServiceLocator.Instance.Resolve<Trekking>().EquippedItems.Count();

        closeButton
            .OnClickAsObservable()
            .Subscribe(_ => {
                ServiceLocator.Instance.Resolve<Trekking>().EquippedItems = EquipedItems;
                audioManager.PlaySE("SE_SELECTED");
                sceneContext?.ModalClose.TrySetResult();
            })
            .AddTo(this);

        GenerateEquipmentableItem();
    }

    void GenerateEquipmentableItem()
    {
        // 装備可能なitem
        EquipmentableItems = ServiceLocator.Instance.Resolve<Trekking>().EquippableItems;

        // 装備中のitemを取得してキャッシュ
        EquipedItems = ServiceLocator.Instance.Resolve<Trekking>().EquippedItems;

        foreach (var item in EquipmentableItems) {
            var obj = Instantiate(EquipmentItemPrefab, EquipmentItemParent);
            var toggle = obj.GetComponent<Toggle>();
            obj.GetComponentInChildren<TMP_Text>().text = item.Name;

            toggle.isOn = EquipedItems.Select(x => x.id).Contains(item.id);

            toggle
                .OnPointerClickAsObservable()
                .Subscribe(x => {
                    audioManager.PlaySE("SE_SELECTED");

                    if (toggle.isOn == false) {
                        EquipedItems.Remove(item);
                    } else {
                        if (EquipedItems.Count() < 2) {
                            EquipedItems.Add(item);
                        } else {
                            toggle.isOn = !toggle.isOn;
                        }
                    }
                }).AddTo(this);
        }
    }
}
