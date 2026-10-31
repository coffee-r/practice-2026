using UnityEngine;
using Zenject;
using KanKikuchi.AudioManager;

public sealed class SceneTransistorOnKeyDown : MonoBehaviour
{
    [Inject]
    private SceneTransistor sceneLoader;

    [SerializeField, Header("ボタン")]
    KeyCode keycode;

    [SerializeField, Header("遷移先シーン")]
    EnumSceneName sceneName;

    [SerializeField, Header("遷移開始までの時間")]
    float duration;

    bool isPressed = false;

    void Update()
    {
        if (isPressed) return;

        if (Input.GetKeyDown(keycode))
        {
            isPressed = true;
            SEManager.Instance.Play(SEPath.KURAGEKOUBOU_BUTTON79);
            Invoke("DelayMethod", duration);
        }
    }

    void DelayMethod()
    {
        sceneLoader.TransistionScene(sceneName);
    }

}
