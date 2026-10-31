// using System.Collections.Generic;

using System;
using System.Collections.Generic;
using System.Diagnostics;
using System.Linq;
using Unity.VisualScripting.Dependencies.NCalc;

/// <summary>
/// 登山を表現します
/// </summary>
public class Trekking
{
    readonly MasterData MasterData;
    TroubleSelector TroubleSelector;
    public List<ItemData> ConsumableItems;
    public List<ItemData> EquippableItems;
    public List<ItemData> EquippedItems; //2つまで
    public List<TroubleData> CurrentTroubles;
    public int CurrentDistance;
    public int CurrentDay {get; private set;}
    public int CurrentMinites {get; private set;}
    public CourseData CurrentCourse;
    public WeatherType CurrentWeather;
    public TerrainType CurrentTerrain;
    public bool IsReachedKatanokoya;
    public bool IsReachedKitadake;
    public bool IsReachedAinodake;

    public Trekking(MasterData masterData, List<ItemData> PackedItems)
    {
        // マスタデータ設定
        MasterData = masterData;

        // トラブル抽選
        TroubleSelector = new TroubleSelector();

        // 使用可能道具設定
        ConsumableItems = PackedItems.Where(x => x.Consumable == true).ToList();

        // 装備可能道具設定
        EquippableItems = PackedItems.Where(x => x.Consumable == false).ToList();

        // 装備中アイテム初期化
        EquippedItems = new List<ItemData>();

        // 現在のトラブル初期化
        CurrentTroubles = new List<TroubleData>();

        // 進行距離は0から
        CurrentDistance = 0;

        // 日程は1から
        CurrentDay = 1;

        // 現在の進行時間を初期化
        CurrentMinites = MasterData.GameSetting.Day1StartMinutes;

        // 現在の天候を初期化
        CurrentWeather = MasterData
                            .WeatherScheduleList
                            .Where(x => CurrentMinites >= x.MinitesStart && CurrentMinites <= x.MinitesEnd)
                            .First()
                            .WeatherType;

        // 現在の地形を初期化
        CurrentTerrain = MasterData
                            .CourseList
                            .Where(x => CurrentDistance >= x.DistanceMeterStart)
                            .Where(x => CurrentDistance <= x.DistanceMeterEnd)
                            .First()
                            .TerrainType;
        
        // 現在のコースを初期化
        CurrentCourse = MasterData
                            .CourseList
                            .Where(x => CurrentDistance >= x.DistanceMeterStart)
                            .Where(x => CurrentDistance <= x.DistanceMeterEnd)
                            .First();
        
        // ゴール達成フラグを初期化
        IsReachedKatanokoya = false;
        IsReachedKitadake = false;
        IsReachedAinodake = false;
    }

    public MoveResult Move()
    {
        // メッセージ
        var resultMessages = new List<string>();

        // 進行メッセージ
        resultMessages.Add("間ノ岳を目指して進んだ。");
        
        // ペナルティ宣言
        var penalty = 0;

        // トラブルによるペナルティ
        if (CurrentTroubles.Count > 0){
            foreach(var trouble in CurrentTroubles) {
                penalty += trouble.MovementPenalty;
                resultMessages.Add(trouble.MovementMessage);
            }
        }
        

        // 天候
        var weather = MasterData.WeatherList.First(x => x.WeatherType == CurrentWeather);

        // 天候に対処できる装備をしているか
        var weatherItem = EquippedItems.Find(x => x.ManageableWeatherTypes.Contains(CurrentWeather));
       
        // 天候ペナルティ
        if (weather.MovementMessage != "" && weatherItem != null) {
            resultMessages.Add(weather.MovementMessage + weatherItem.Name + " を使って対応した。");
        } else if (weather.MovementMessage != "") {
            resultMessages.Add(weather.MovementMessage);
            penalty += weather.MovementPenalty;
        } else {
            // 何もしない
        }

        // 地形
        var terrain = MasterData.TerrainList.First(x => x.TerrainType == CurrentTerrain);

        // 地形に対処できる装備をしているか
        var terrainItem = EquippedItems.Find(x => x.ManageableTerrainTypes.Contains(CurrentTerrain));

        // 地形
        if (terrain.MovementMessage != "" && terrainItem != null) {
            resultMessages.Add(terrain.MovementMessage + terrainItem.Name + " を使って対応した。");
        } else if (terrain.MovementMessage != "") {
            resultMessages.Add(terrain.MovementMessage);
            penalty += terrain.MovementPenalty;
        } else {
            // 何もしない
        }

        // 距離進行
        var nowCource = MasterData.CourseList.Where(x => x.DistanceMeterStart <= CurrentDistance && x.DistanceMeterEnd >= CurrentDistance).First();
        var progress = nowCource.TrekkerMovableSpeed - penalty;
        if (progress < 0) progress = 0;
        CurrentDistance += progress;
        resultMessages.Add("距離が " + progress + "m 進んだ。");

        // 時間進行
        CurrentMinites += MasterData.GameSetting.BaseMinitesPerStep;
        resultMessages.Add(MasterData.GameSetting.BaseMinitesPerStep + "分が経過した。");

        // 進行後のコース情報
        var nextCource = MasterData.CourseList.Where(x => x.DistanceMeterStart <= CurrentDistance && x.DistanceMeterEnd >= CurrentDistance).First();
        CurrentCourse = nextCource;

        // 進行後の天候
        CurrentWeather = MasterData
                            .WeatherScheduleList
                            .Where(x => CurrentMinites >= x.MinitesStart && CurrentMinites <= x.MinitesEnd)
                            .First()
                            .WeatherType;

        // 進行後の地形
        CurrentTerrain = MasterData
                            .CourseList
                            .Where(x => CurrentDistance >= x.DistanceMeterStart)
                            .Where(x => CurrentDistance <= x.DistanceMeterEnd)
                            .First()
                            .TerrainType;

        // トラブル発生抽選
        var troubleRandom = new Random();
        if (troubleRandom.Next(100) <= nextCource.TroubleOccurrencePercent) {
            var troubleType = TroubleSelector.GetRandomTrouble(MasterData.TroubleOccurrenceWeightList);
            var trouble = MasterData.TroubleList.Where(x => x.TroubleType == troubleType).First();
            CurrentTroubles.Add(trouble);
            resultMessages.Add(trouble.MovementMessage);
        }

        // 次のメッセージ
        var nextReadyMessage = new List<string>();
        nextReadyMessage.Add("天気が変わり雨になった。");

        // 肩の小屋判定
        bool reachKatanokoya = false;
        if (IsReachedKatanokoya == false && CurrentDistance >= MasterData.GameSetting.Level1TargetDistanceMeter) {
            IsReachedKatanokoya = true;
            reachKatanokoya = true;
            CurrentDay = 2;
            CurrentTroubles = new List<TroubleData>(); // トラブルリセット
            CurrentMinites = MasterData.GameSetting.Day2StartMinutes;
            resultMessages.Add("小屋に到着した！");
        }

        // 北岳判定
        bool reachKitadake = false;
        if (IsReachedKitadake == false && CurrentDistance >= MasterData.GameSetting.Level2TargetDistanceMeter) {
            IsReachedKitadake = true;
            reachKitadake = true;
            resultMessages.Add("山頂に到着した！");
        }

        // 間ノ岳判定
        bool reachAinodake = false;
        if (IsReachedAinodake == false && CurrentDistance >= MasterData.GameSetting.Level3TargetDistanceMeter) {
            IsReachedAinodake = true;
            reachAinodake = true;
            resultMessages.Add("山頂に到着した！");
        }

        // 時間制限判定
        bool timeUp = false;
        if (CurrentDay == 1 && CurrentMinites > MasterData.GameSetting.Day1TimeLimitMinutes) {
            timeUp = true;
        }
        if (CurrentDay == 2 && CurrentMinites > MasterData.GameSetting.Day2TimeLimitMinutes) {
            timeUp = true;
        }
        return new MoveResult(resultMessages, nextReadyMessage, reachKatanokoya, reachKitadake, reachAinodake, timeUp);
    }

    public ItemUseResult UseItem(int? itemId)
    {
        if (itemId == null) {
            return new ItemUseResult(new List<string>());
        }

        // メッセージ
        var resultMessages = new List<string>();

        // アイテム使用
        var item = ConsumableItems.Where(x => x.id == itemId).First();
        ConsumableItems.Remove(item);
        resultMessages.Add(item.Name + " を消費した。");

        // アイテム使用によりトラブルを1つ解決する
        var resolveTrouble = CurrentTroubles
                        .Where(x => item.ResolvableTroubleTypes.Contains(x.TroubleType))
                        .FirstOrDefault();
        
        // 解消有無によってメッセージ分岐
        if (resolveTrouble == null) {
            resultMessages.Add("今の状況で役に立たなかった。");
        } else {
            CurrentTroubles.Remove(resolveTrouble);
            resultMessages.Add(resolveTrouble.TroubleType + " が解消された。");
        }

        return new ItemUseResult(resultMessages);
    }

    public void ToLevel2()
    {
        CurrentDay = 2;
        CurrentMinites = MasterData.GameSetting.Day2StartMinutes;
        CurrentTroubles = new List<TroubleData>();
    }

}
