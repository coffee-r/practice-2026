
[System.Serializable]
public class ItemData
{
    public int id;
    public string Name;
    public int WeightGrams;
    public bool Consumable;
    public TroubleType[] ResolvableTroubleTypes;
    public WeatherType[] ManageableWeatherTypes;
    public TerrainType[] ManageableTerrainTypes;
}
