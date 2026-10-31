using UnityEngine;
using UnityEngine.UI;
using System.Collections.Generic;
using ZLinq;
using System.Linq;

public class ItemToggleGenerator : MonoBehaviour
{
    public GameObject itemTogglePrefab;
    public Transform toggleParent;

    private List<ToggleItemPair> toggleItemPairs = new List<ToggleItemPair>();

    void Start()
    {
        GenerateItemToggles();
    }

    void GenerateItemToggles()
    {
        var masterData = ServiceLocator.Instance.Resolve<MasterData>();
        foreach (var item in masterData.ItemList)
        {
            var toggleObj = Instantiate(itemTogglePrefab, toggleParent);
            var toggle = toggleObj.GetComponent<Toggle>();
            var label = toggleObj.GetComponentInChildren<Text>();
            label.text = item.Name;

            toggle.isOn = false;

            toggleItemPairs.Add(new ToggleItemPair(toggle, item));

            // optional: 状態変化ログ
            toggle.onValueChanged.AddListener(isOn =>
            {
                Debug.Log($"{item.Name} is {(isOn ? "equipped" : "unequipped")}");
            });
        }
    }

    // ←★ これでONになってるItemData一覧が取れる！
    public List<ItemData> GetSelectedItems()
    {
        return toggleItemPairs
            .Where(pair => pair.toggle.isOn)
            .Select(pair => pair.itemData)
            .ToList();
    }
}