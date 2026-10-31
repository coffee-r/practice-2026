using Cysharp.Threading.Tasks;
using R3;
using UnityEngine;
using UnityEngine.SceneManagement;
using UnityEngine.UI;

public class TitlePresenter : MonoBehaviour
{
    [SerializeField] Button gameStartButton;
    [SerializeField] Button licenceButton;
    SceneRouter sceneRouter;
    IScreenTransitionEffect iScreenTransitionEffect;
    AudioManager audioManager;

    // Start is called once before the first execution of Update after the MonoBehaviour is created
    void Start()
    {
        sceneRouter = ServiceLocator.Instance.Resolve<SceneRouter>();
        iScreenTransitionEffect = ServiceLocator.Instance.Resolve<IScreenTransitionEffect>("Default");
        audioManager = ServiceLocator.Instance.Resolve<AudioManager>();
        audioManager.PlayBGM("kewashii-tozandou");

        gameStartButton
            .OnClickAsObservable()
            .SubscribeAwait(async (x, ct) => 
            {
                audioManager.PlaySE("SE_SELECTED");
                await sceneRouter.NavigateToAsync("Scenes/Planning", iScreenTransitionEffect, ct);
            }).AddTo(this);

        licenceButton
            .OnClickAsObservable()
            .SubscribeAwait(async (x, ct) => 
            {
                audioManager.PlaySE("SE_SELECTED");
                var uts = new UniTaskCompletionSource();
                ServiceLocator.Instance.Resolve<SceneContext>().ModalClose = uts;
                await sceneRouter.ShowModalAsync("Scenes/Licence", ct);
            }).AddTo(this);
    }
}
