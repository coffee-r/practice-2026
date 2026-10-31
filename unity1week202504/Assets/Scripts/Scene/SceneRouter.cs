using System;
using System.Threading;
using Cysharp.Threading.Tasks;
using R3;
using UnityEngine;
using UnityEngine.SceneManagement;

/// <summary>
/// シーン遷移を管理するクラス
/// </summary>
public class SceneRouter : IDisposable
{
    readonly CompositeDisposable compositeDisposable = new CompositeDisposable();

    public async UniTask NavigateToAsync(string sceneName, IScreenTransitionEffect iScreenTransitionEffect = null, CancellationToken cancellation = default)
    {
        // 画面遷移エフェクトに指定が無ければ何もアニメーションしない実装を使う
        iScreenTransitionEffect ??= new ScreenTransitionEffectNone();

        // 現在のアクティブなシーンを取得
        var currentActiveScene = SceneManager.GetActiveScene();

        // アニメーション
        await iScreenTransitionEffect.PlayEnterAnimation();

        // 現在のシーンをアンロード
        await SceneManager.UnloadSceneAsync(currentActiveScene);

        // 次のシーンをロード
        await SceneManager.LoadSceneAsync(sceneName, LoadSceneMode.Additive);
        SceneManager.SetActiveScene(SceneManager.GetSceneByName(sceneName));

        // アニメーション
        await iScreenTransitionEffect.PlayExitAnimation();
    }

    /// <summary>
    /// 現状は1階層だけのModalを想定して作ってます
    /// 多段階は対応してないです。
    /// </summary>
    public async UniTask ShowModalAsync(string sceneName, CancellationToken cancellation = default)
    {
        // 現在のアクティブなシーンを取得
        var currentActiveScene = SceneManager.GetActiveScene();

        // 次のシーンをロード
        await SceneManager.LoadSceneAsync(sceneName, LoadSceneMode.Additive);
        SceneManager.SetActiveScene(SceneManager.GetSceneByName(sceneName));

        // modalを閉じるまで待つ
        var sceneContext = ServiceLocator.Instance.Resolve<SceneContext>();
        await sceneContext.ModalClose.Task.AttachExternalCancellation(cancellation);

        // モーダルシーンをアンロード
        await SceneManager.UnloadSceneAsync(SceneManager.GetSceneByName(sceneName));
        sceneContext.Reset();

        // アクティブなシーンを元に戻す
        SceneManager.SetActiveScene(SceneManager.GetSceneByName(currentActiveScene.name));
    }

    public async UniTask<int?> ShowItemUseModalAsync(string sceneName, CancellationToken cancellation = default)
    {
        // 現在のアクティブなシーンを取得
        var currentActiveScene = SceneManager.GetActiveScene();

        // 次のシーンをロード
        await SceneManager.LoadSceneAsync(sceneName, LoadSceneMode.Additive);
        SceneManager.SetActiveScene(SceneManager.GetSceneByName(sceneName));

        // modalを閉じるまで待つ
        var sceneContext = ServiceLocator.Instance.Resolve<SceneContext>();
        await sceneContext.ModalClose.Task.AttachExternalCancellation(cancellation);

        // モーダルシーンをアンロード
        await SceneManager.UnloadSceneAsync(SceneManager.GetSceneByName(sceneName));
        var itemId = sceneContext.UseItemId;
        sceneContext.Reset();

        // アクティブなシーンを元に戻す
        SceneManager.SetActiveScene(SceneManager.GetSceneByName(currentActiveScene.name));

        return itemId;
    }

    public void Dispose()
    {
        compositeDisposable.Dispose();
    }
}
