using System.Threading;
using Cysharp.Threading.Tasks;

/// <summary>
/// 画面遷移アニメーションのインターフェース
/// </summary>
public interface IScreenTransitionEffect
{
    /// <summary>
    /// 開始アニメーション
    /// </summary>
    /// <returns></returns>
    UniTask PlayEnterAnimation(CancellationToken cancellation = default);

    /// <summary>
    /// 終了アニメーション
    /// </summary>
    /// <returns></returns>
    UniTask PlayExitAnimation(CancellationToken cancellation = default);
}
