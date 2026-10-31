
using System.Collections.Generic;
using Cysharp.Threading.Tasks;

#nullable enable

/// <summary>
/// シーンを跨いで渡すデータを定義します
/// 1週間のゲームジャムである規模を踏まえて、どのシーンでもこのクラスを使います (なので渡す際にsetしない無駄なプロパティありますけど無視してください)
/// </summary>
public class SceneContext
{
    public List<ItemData>? PackedItems;
    public int? UseItemId;
    public bool Continue = false;

    public UniTaskCompletionSource? ModalClose {get; set;}

    public void Reset()
    {
        PackedItems = null;
        UseItemId = null;
        ModalClose = null;
    }
}