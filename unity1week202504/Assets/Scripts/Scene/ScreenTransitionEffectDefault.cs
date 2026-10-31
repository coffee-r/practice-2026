using UnityEngine;
using Cysharp.Threading.Tasks;
using Sirenix.OdinInspector;
using UnityEngine.UI;
using System.Threading;
using DG.Tweening;

/// <summary>
/// IScreenTransitionEffectの実装
/// </summary>
public class ScreenTransitionEffectDefault : MonoBehaviour, IScreenTransitionEffect
{
    [SerializeField] CanvasGroup overrayCanvasGroup;

    [Button("Play Enter Animation")]
    public async UniTask PlayEnterAnimation(CancellationToken cancellation = default)
    {
        overrayCanvasGroup.alpha = 0;

        await overrayCanvasGroup
                .DOFade(1.0f, 1.0f)
                .SetEase(Ease.OutSine)
                .AsyncWaitForCompletion()
                .AsUniTask()
                .AttachExternalCancellation(cancellation);
    }

    [Button("Play Exit Animation")]
    public async UniTask PlayExitAnimation(CancellationToken cancellation = default)
    {
        await overrayCanvasGroup
                .DOFade(0.0f, 1.0f)
                .SetEase(Ease.OutSine)
                .AsyncWaitForCompletion()
                .AsUniTask()
                .AttachExternalCancellation(cancellation);
    }
}
