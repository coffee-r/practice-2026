using Cysharp.Threading.Tasks;
using R3;
using TMPro;
using UnityEngine;
using UnityEngine.UI;

public class LicencePresenter : MonoBehaviour
{
    [SerializeField] Button closeButton;
    [SerializeField] TMP_Text licenceText;
    SceneContext sceneContext;
    AudioManager audioManager;

    // Start is called once before the first execution of Update after the MonoBehaviour is created
    void Start()
    {
        sceneContext = ServiceLocator.Instance.Resolve<SceneContext>();
        audioManager = ServiceLocator.Instance.Resolve<AudioManager>();

        // Modalを閉じたらSceneRouterに閉じたことが伝わるようにする
        closeButton
            .OnClickAsObservable()
            .Subscribe(_ => 
            {
                audioManager.PlaySE("SE_SELECTED");
                sceneContext?.ModalClose.TrySetResult();
            })
            .AddTo(this);
    }
}
