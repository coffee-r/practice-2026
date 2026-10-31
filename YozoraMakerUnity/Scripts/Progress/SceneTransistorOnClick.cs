using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UnityEngine.SceneManagement;
using UniRx;
using Zenject;

public sealed class SceneTransistorOnClick : MonoBehaviour
{
    [Inject]
    private SceneTransistor sceneLoader;

    private Button button => GetComponent<Button>();

    [SerializeField, Header("遷移先シーン")]
    EnumSceneName sceneName;

    [SerializeField, Header("遷移開始までの時間")]
    float duration;

    void Start()
    {
        button
            .OnClickAsObservable()
            .TakeUntilDestroy(this)
            .ThrottleFirst(System.TimeSpan.FromMilliseconds(1000))
            .Subscribe(x => Exec())
            .AddTo(this);
    }

    void Exec()
    {
        Observable.Timer(System.TimeSpan.FromMilliseconds(duration)).Subscribe(_ => sceneLoader.TransistionScene(sceneName)).AddTo(this);
    }

}
