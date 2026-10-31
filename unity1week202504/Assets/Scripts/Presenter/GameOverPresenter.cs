using Cysharp.Threading.Tasks;
using R3;
using UnityEngine;
using UnityEngine.UI;

public class GameOverPresenter : MonoBehaviour
{
    [SerializeField] Button TitleButton;
    SceneRouter sceneRouter;
    IScreenTransitionEffect iScreenTransitionEffect;
    AudioManager audioManager;

    // Start is called once before the first execution of Update after the MonoBehaviour is created
    void Start()
    {
        sceneRouter = ServiceLocator.Instance.Resolve<SceneRouter>();
        iScreenTransitionEffect = ServiceLocator.Instance.Resolve<IScreenTransitionEffect>("Default");
        audioManager = ServiceLocator.Instance.Resolve<AudioManager>();

        TitleButton
            .OnClickAsObservable()
            .SubscribeAwait(async (x, ct) => 
            {
                audioManager.PlaySE("SE_SELECTED");
                ServiceLocator.Instance.Resolve<SceneContext>().Continue = false;
                await sceneRouter.NavigateToAsync("Scenes/Title", iScreenTransitionEffect, ct);
            }).AddTo(this);
    }
}
