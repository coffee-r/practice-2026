using Cysharp.Threading.Tasks;
using DG.Tweening;
using TMPro;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Video;

public class Goal2Presenter : MonoBehaviour
{
    [SerializeField] Image Image0;
    [SerializeField] Image Image1;
    [SerializeField] VideoPlayer Video0;
    [SerializeField] Image Image3;
    [SerializeField] Image Image4;
    [SerializeField] TMP_Text text0;
    [SerializeField] TMP_Text text1;
    [SerializeField] Image Image5;
    [SerializeField] Image Image6;

    SceneRouter sceneRouter;
    IScreenTransitionEffect iScreenTransitionEffect;
    AudioManager audioManager;

    async void Start()
    {
        sceneRouter = ServiceLocator.Instance.Resolve<SceneRouter>();
        iScreenTransitionEffect = ServiceLocator.Instance.Resolve<IScreenTransitionEffect>("Default");
        audioManager = ServiceLocator.Instance.Resolve<AudioManager>();

        audioManager.PlayBGM("Goal1", 0, false);

        Image0.gameObject.SetActive(true);

        await UniTask.WaitForSeconds(5);

        Image1.gameObject.SetActive(true);
        Image0.gameObject.SetActive(false);
        Video0.gameObject.SetActive(true);

        await UniTask.WaitForSeconds(5);

        Image1.gameObject.SetActive(false);

        await UniTask.WaitForSeconds(6);

        Image3.gameObject.SetActive(true);
        Video0.gameObject.SetActive(false);

        await UniTask.WaitForSeconds(5);

        Image4.gameObject.SetActive(true);
        Image3.gameObject.SetActive(false);

        await UniTask.WaitForSeconds(5);

        text0.gameObject.SetActive(true);
        text1.gameObject.SetActive(true);
        Image6.gameObject.SetActive(true);

        var canvasGroup = Image6.GetComponent<CanvasGroup>();
        await canvasGroup.DOFade(1.0f, 3).AsyncWaitForCompletion();

        await UniTask.WaitForSeconds(2);

        text0.gameObject.SetActive(false);
        text1.gameObject.SetActive(false);

        await UniTask.WaitForSeconds(3);

        text0.DOFade(0.0f, 2);
        await text1.DOFade(0.0f, 2).AsyncWaitForCompletion();

        Image5.gameObject.SetActive(true);
        await canvasGroup.DOFade(0.0f, 1).AsyncWaitForCompletion();
        await UniTask.WaitForSeconds(4);

        audioManager.PlayBGM("kewashii-tozandou");
        ServiceLocator.Instance.Resolve<SceneContext>().Continue = true;
        await sceneRouter.NavigateToAsync("Scenes/TrekkingLevel1", iScreenTransitionEffect);
    }
}
