using System.Collections.Generic;
using System.Linq;
using Cysharp.Threading.Tasks;
using R3;
using R3.Triggers;
using TMPro;
using UnityEngine;
using UnityEngine.UI;

public class PlanningPresenter : MonoBehaviour
{
    [SerializeField] GameObject itemTogglePrefab;
    [SerializeField] Transform toggleParent;
    [SerializeField] Button allToggleOnButton;
    [SerializeField] Button allToggleOffButton;
    [SerializeField] Button toTrekkingButton;
    [SerializeField] TMP_Text weightText;
    List<ToggleItemPair> toggleItemPairs = new List<ToggleItemPair>();
    MasterData masterData;
    SceneRouter sceneRouter;
    IScreenTransitionEffect iScreenTransitionEffect;
    AudioManager audioManager;
    SceneContext sceneContext;

    // Start is called once before the first execution of Update after the MonoBehaviour is created
    void Start()
    {
        masterData = ServiceLocator.Instance.Resolve<MasterData>();
        sceneRouter = ServiceLocator.Instance.Resolve<SceneRouter>();
        iScreenTransitionEffect = ServiceLocator.Instance.Resolve<IScreenTransitionEffect>("Default");
        audioManager = ServiceLocator.Instance.Resolve<AudioManager>();
        sceneContext = ServiceLocator.Instance.Resolve<SceneContext>();

        GenerateItemToggles();

        toTrekkingButton
            .OnClickAsObservable()
            .Where(x => IsWeightOver() == false)
            .SubscribeAwait(async (x, ct) => 
            {
                audioManager.PlaySE("SE_SELECTED");
                sceneContext.PackedItems = toggleItemPairs
                                            .Where(x => x.toggle.isOn == true)
                                            .Select(x => x.itemData)
                                            .ToList();
                Debug.Log(sceneContext.PackedItems);
                await sceneRouter.NavigateToAsync("Scenes/TrekkingLevel1", iScreenTransitionEffect, ct);
            }).AddTo(this);

        allToggleOnButton
            .OnClickAsObservable()
            .Subscribe(_ => {
                audioManager.PlaySE("SE_SELECTED");
                
                foreach (var toggleItemPair in toggleItemPairs)
                {
                    toggleItemPair.toggle.isOn = true;
                }
            }).AddTo(this);

         allToggleOffButton
            .OnClickAsObservable()
            .Subscribe(_ => {
                audioManager.PlaySE("SE_SELECTED");
                
                foreach (var toggleItemPair in toggleItemPairs)
                {
                    toggleItemPair.toggle.isOn = false;
                }
            }).AddTo(this);
    }

    void GenerateItemToggles()
    {
        foreach (var item in masterData.ItemList)
        {
            var toggleObj = Instantiate(itemTogglePrefab, toggleParent);
            var toggle = toggleObj.GetComponent<Toggle>();
            var label = toggleObj.GetComponentInChildren<TMP_Text>();
            label.text = item.Name;

            toggle.isOn = false;

            toggleItemPairs.Add(new ToggleItemPair(toggle, item));

            // optional: 状態変化ログ
            toggle
                .OnValueChangedAsObservable()
                .Subscribe(isOn =>
                {
                    UpdateWeightText();
                }).AddTo(this);
            
            toggle
                .OnPointerClickAsObservable()
                .Subscribe(x => {
                    audioManager.PlaySE("SE_SELECTED");
                }).AddTo(this);
        }
    }

    void UpdateWeightText()
    {
        // 現在の重量を取得
        var currentWeight = toggleItemPairs
                            .Where(x => x.toggle.isOn == true)
                            .Sum(y => y.itemData.WeightGrams);
        
        // 最大重量を取得
        var maxWeight = masterData.GameSetting.MaxWeightGrams;

        // テキストに文字を設定
        weightText.text = "ザック重量 " + currentWeight + " / " + maxWeight + " g";

        // テキストの色を設定
        if (currentWeight <= maxWeight) {
            weightText.color = Color.white;
        } else {
            weightText.color = Color.red;
        }
    }

    bool IsWeightOver()
    {
        // 現在の重量を取得
        var currentWeight = toggleItemPairs
                            .Where(x => x.toggle.isOn == true)
                            .Sum(y => y.itemData.WeightGrams);
        
        // 最大重量を取得
        var maxWeight = masterData.GameSetting.MaxWeightGrams;

        return currentWeight > maxWeight;
    }
}
