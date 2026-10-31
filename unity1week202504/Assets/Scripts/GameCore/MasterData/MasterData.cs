using System.Collections.Generic;
using UnityEngine;

[CreateAssetMenu(fileName = "MasterData", menuName = "Scriptable Objects/MasterData")]
public class MasterData : ScriptableObject
{
    public GameSettingData GameSetting;
    public List<ItemData> ItemList;
    public List<CourseData> CourseList;
    public List<TerrainData> TerrainList;
    public List<WeatherScheduleData> WeatherScheduleList;
    public List<WeatherData> WeatherList;
    public List<TroubleData> TroubleList;
    public List<TroubleOccurrenceWeightData> TroubleOccurrenceWeightList;
}
