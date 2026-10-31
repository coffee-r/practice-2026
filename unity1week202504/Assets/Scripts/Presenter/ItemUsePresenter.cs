using System.Collections.Generic;
using Cysharp.Threading.Tasks;
using R3;
using TMPro;
using UnityEngine;
using UnityEngine.UI;

public class ItemUsePresenter : MonoBehaviour
{
    [SerializeField] GameObject UseItemPrefab;
    [SerializeField] Transform UseItemParent;
    [SerializeField] Button closeButton;
    List<ItemData> ConsumableItems;
    SceneContext sceneContext;
    AudioManager audioManager;

    // Start is called once before the first execution of Update after the MonoBehaviour is created
    void Start()
    {
        sceneContext = ServiceLocator.Instance.Resolve<SceneContext>();
        audioManager = ServiceLocator.Instance.Resolve<AudioManager>();

        ConsumableItems = ServiceLocator.Instance.Resolve<Trekking>().ConsumableItems;

        closeButton
            .OnClickAsObservable()
            .Subscribe(_ => {
                audioManager.PlaySE("SE_SELECTED");
                sceneContext.UseItemId = null;
                sceneContext?.ModalClose.TrySetResult();
            })
            .AddTo(this);

        GenerateUseItem();
    }

    void GenerateUseItem()
    {
        foreach (var item in ConsumableItems)
        {
            var useItemObj = Instantiate(UseItemPrefab, UseItemParent);
            var component = useItemObj.GetComponent<ConsumableItem>();
            component.label.text = item.Name;

            component
                .button
                .OnClickAsObservable()
                .Subscribe(x => {
                    audioManager.PlaySE("SE_SELECTED");
                    sceneContext.UseItemId = item.id;
                    sceneContext?.ModalClose.TrySetResult();
                }).AddTo(this);
        }
    }
}
