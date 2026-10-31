using UnityEngine.UI;

public class ToggleItemPair
{
    public Toggle toggle;
    public ItemData itemData;

    public ToggleItemPair(Toggle toggle, ItemData itemData)
    {
        this.toggle = toggle;
        this.itemData = itemData;
    }
}