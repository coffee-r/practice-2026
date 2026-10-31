using System.Collections.Generic;
using System.Threading;
using Cysharp.Threading.Tasks;
using UnityEngine;
using System.Linq;
using TMPro;

/// <summary>
/// テキストアドベンチャー風のテキスト表示を制御するクラス
/// </summary>
public class MessageText : MonoBehaviour
{
    private TMP_Text textComponent; // テキストを表示するためのUI Text
    
    private CancellationTokenSource _cancellationTokenSource;
    
    void Start()
    {
        textComponent = GetComponent<TMP_Text>();
        _cancellationTokenSource = new CancellationTokenSource();
    }
    
    private void OnDestroy()
    {
        _cancellationTokenSource?.Cancel();
        _cancellationTokenSource?.Dispose();
    }
    
    /// <summary>
    /// メッセージリストを順番に表示します
    /// 各メッセージは改行され、改行ごとにクリック待ちします
    /// 1文字ごとに1フレーム待機します
    /// </summary>
    /// <param name="messages">表示するメッセージのリスト</param>
    public async UniTask ShowInOrder(List<string> messages)
    {
        textComponent.text = string.Empty;
        
        foreach (var message in messages)
        {
            await DisplayTextWithTypingEffect(message);
            // 新しいメッセージの前に改行を追加
            if (message != messages[messages.Count - 1])
            {
                textComponent.text += "\n";
            }
        }

        await WaitForClick();
    }

    public async UniTask ShowStatusMessage()
    {
        var messages = new List<string>();
        var trekking = ServiceLocator.Instance.Resolve<Trekking>();

        // 現在時刻のメッセージ
        var hour = trekking.CurrentMinites / 60;
        var minites = trekking.CurrentMinites % 60;
        if (minites == 0) {
            messages.Add("時刻 | " + hour + ":00");
        } else {
            messages.Add("時刻 | " + hour +":" + minites);
        }
        
        // 標高
        messages.Add("標高 | " + trekking.CurrentCourse.Altitude + "m");

        // 距離
        messages.Add("距離 | " + trekking.CurrentDistance + "m");

        // 装備
        if (trekking.EquippedItems.Count > 0){
            var equip = trekking.EquippedItems.Select(x => x.Name).Aggregate((x, y) => x + ", " + y).ToString();
            messages.Add("装備 | " + equip);
        } else {
            messages.Add("装備 | なし");
        }
        

        // 天気
        messages.Add("天気 | " + trekking.CurrentWeather.ToString());

        // 地形
        messages.Add("地形 | " + trekking.CurrentTerrain.ToString());

        // 状態
        if (trekking.CurrentTroubles.Count > 0){
            var troubles = trekking.CurrentTroubles.Select(x => x.TroubleType.ToString()).Aggregate((x, y) => x + ", " + y).ToString();
            messages.Add("状態 | " + troubles);
        } else {
            messages.Add("状態 | 問題なし");
        }

        // 目標
        var masterData = ServiceLocator.Instance.Resolve<MasterData>();
        if (trekking.IsReachedKatanokoya == false) {
            messages.Add("小屋まであと " + (masterData.GameSetting.Level1TargetDistanceMeter - trekking.CurrentDistance) + "mだ。");
        } else if (trekking.IsReachedKitadake == false) {
            messages.Add("山頂まであと " + (masterData.GameSetting.Level2TargetDistanceMeter - trekking.CurrentDistance) + "mだ。");
        } else{
            messages.Add("山頂まであと " + (masterData.GameSetting.Level3TargetDistanceMeter - trekking.CurrentDistance) + "mだ。");
        }

        textComponent.text = string.Empty;
        
        foreach (var message in messages)
        {
            await DisplayTextWithTypingEffect(message);
            
            // 新しいメッセージの前に改行を追加
            if (message != messages[messages.Count - 1])
            {
                textComponent.text += "\n";
            }
        }
    }
    
    /// <summary>
    /// テキストを1文字ずつタイピング効果で表示します
    /// </summary>
    /// <param name="text">表示するテキスト</param>
    private async UniTask DisplayTextWithTypingEffect(string text)
    {
        int currentLength = textComponent.text.Length;
        string originalText = textComponent.text;
        
        for (int i = 0; i < text.Length; i++)
        {
            // キャンセルされていたら中断
            if (_cancellationTokenSource.Token.IsCancellationRequested)
                return;
            
            // 現在のテキストに1文字追加
            textComponent.text = originalText + text.Substring(0, i + 1);
            
            // 1フレーム待機
            if (i%2 == 0) {
                await UniTask.DelayFrame(1, cancellationToken: _cancellationTokenSource.Token);
            }
        }
    }
    
    /// <summary>
    /// ユーザーのクリックを待ちます
    /// </summary>
    private async UniTask WaitForClick()
    {        
        // クリック表示などの処理を追加するならここに
        textComponent.text += " ▼";
        
        // クリックを待機
        await UniTask.WaitUntil(() => Input.GetMouseButtonDown(0) || Input.touchCount > 0 && Input.GetTouch(0).phase == TouchPhase.Began, 
            cancellationToken: _cancellationTokenSource.Token);
        
        // クリック表示を削除
        textComponent.text = textComponent.text.Replace(" ▼", "");
                
        // クリック入力のクリア待ち
        await UniTask.WaitUntil(() => !(Input.GetMouseButton(0) || Input.touchCount > 0), 
            cancellationToken: _cancellationTokenSource.Token);
    }
    
    /// <summary>
    /// 現在表示中のテキストをすべてスキップします
    /// </summary>
    public void SkipCurrentText()
    {
        _cancellationTokenSource.Cancel();
        _cancellationTokenSource.Dispose();
        _cancellationTokenSource = new CancellationTokenSource();
    }
}