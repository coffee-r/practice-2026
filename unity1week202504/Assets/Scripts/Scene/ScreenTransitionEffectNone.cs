using System.Threading;
using Cysharp.Threading.Tasks;

public class ScreenTransitionEffectNone : IScreenTransitionEffect
{
    public async UniTask PlayEnterAnimation(CancellationToken cancellation)
    {
        await UniTask.CompletedTask;
    }

    public async UniTask PlayExitAnimation(CancellationToken cancellation)
    {
        await UniTask.CompletedTask;
    }
}
