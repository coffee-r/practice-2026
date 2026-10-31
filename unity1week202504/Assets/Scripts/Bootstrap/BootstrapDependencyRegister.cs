using UnityEngine;

/// <summary>
/// Bootstrapシーンの依存関係のセットアップを行います。
/// </summary>
public class BootstrapDependencyRegister : MonoBehaviour
{
    [SerializeField] ScreenTransitionEffectDefault screenTransitionEffectDefault;
    [SerializeField] AudioManager audioManager;
    [SerializeField] MasterData MasterData;

    void Awake()
    {
        ServiceLocator.Instance.Register(MasterData);
        ServiceLocator.Instance.Register(audioManager);
        ServiceLocator.Instance.Register(new SceneContext());
        ServiceLocator.Instance.Register<IScreenTransitionEffect>(screenTransitionEffectDefault, "Default");
        ServiceLocator.Instance.Register(new SceneRouter());
    }

    void OnDestroy()
    {
        ServiceLocator.Instance.UnRegister<AudioManager>();
        ServiceLocator.Instance.UnRegister<SceneContext>();
        ServiceLocator.Instance.UnRegister<SceneRouter>();
        ServiceLocator.Instance.UnRegister<IScreenTransitionEffect>("Default");
    }
}
