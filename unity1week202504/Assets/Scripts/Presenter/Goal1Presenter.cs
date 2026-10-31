using Cysharp.Threading.Tasks;
using DG.Tweening;
using TMPro;
using Unity.VisualScripting;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.Video;

public class Goal1Presenter : MonoBehaviour
{
    [SerializeField] Image Image0;
    [SerializeField] Image Image1;
    [SerializeField] Image Image2;
    [SerializeField] Image Image3;
    [SerializeField] Image Image4;
    [SerializeField] TMP_Text text0;
    [SerializeField] TMP_Text text1;
    [SerializeField] TMP_Text text2;

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

        await UniTask.WaitForSeconds(5);

        Image2.gameObject.SetActive(true);
        Image1.gameObject.SetActive(false);

        await UniTask.WaitForSeconds(5);

        Image3.gameObject.SetActive(true);
        Image2.gameObject.SetActive(false);

        await UniTask.WaitForSeconds(5);

        Image4.gameObject.SetActive(true);
        text0.gameObject.SetActive(true);
        text1.gameObject.SetActive(true);

        var canvasGroup = Image4.GetComponent<CanvasGroup>();
        await canvasGroup.DOFade(1.0f, 3).AsyncWaitForCompletion();

        await UniTask.WaitForSeconds(5);

        text0.gameObject.SetActive(false);
        text1.gameObject.SetActive(false);

        await UniTask.WaitForSeconds(2);

        text2.gameObject.SetActive(true);
        await UniTask.WaitForSeconds(3);
        text2.gameObject.SetActive(false);
        await UniTask.WaitForSeconds(2);

        ServiceLocator.Instance.Resolve<SceneContext>().Continue = true;
        await sceneRouter.NavigateToAsync("Scenes/TrekkingLevel1", iScreenTransitionEffect);
    }
}
