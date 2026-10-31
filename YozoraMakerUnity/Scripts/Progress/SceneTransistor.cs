using System;
using System.Threading;
using System.Runtime.CompilerServices;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;
using Zenject;
using UniRx.Async;
using DG.Tweening;

using System.Threading.Tasks;

public sealed class SceneTransistor : MonoBehaviour
{
    [Inject]
    ZenjectSceneLoader zenjctSceneLoader;

    [SerializeField, Header("フェードアウトオブジェクト")]
    Image imageFadeOut;

    [SerializeField, Header("フェードインオブジェクト")]
    Image imageFadeIn;

    [SerializeField, Header("フェード時間")]
    float fadeDuration;

    [SerializeField, Header("アニメーション")]
    Ease ease;


    private async void Start()
    {
        
        imageFadeOut.rectTransform.localScale = Vector3.one;

        if(SceneManager.GetActiveScene().name == "Title")
        {
            imageFadeOut.color = Color.black;
            imageFadeOut.GetComponent<CanvasGroup>().alpha = 1;
            imageFadeOut.GetComponent<CanvasGroup>().DOFade(0, fadeDuration).SetEase(ease);
        }
        else
        {
            imageFadeOut.color = Color.yellow;
            await UniTask.Delay(100);
            imageFadeOut.rectTransform.DOScaleX(0, fadeDuration).SetEase(ease);
        }

        
    }

    public async void TransistionScene(EnumSceneName sceneName, CancellationToken token = default)
    {
        await imageFadeIn.rectTransform.DOScale(1, fadeDuration).SetEase(ease).ToAwaiter(token);
        await zenjctSceneLoader.LoadSceneAsync(sceneName.GetTypeName(), LoadSceneMode.Single);
    }

    public async void TransistionScene(EnumSceneName sceneName, Score score, CancellationToken token = default)
    {
        await imageFadeIn.rectTransform.DOScale(1, fadeDuration).SetEase(ease).ToAwaiter(token);
        await zenjctSceneLoader.LoadSceneAsync(sceneName.GetTypeName(), LoadSceneMode.Single,
            (DiContainer container) =>
            {
                container.Bind<Score>().FromInstance(score).AsCached();
            });
    }

}
