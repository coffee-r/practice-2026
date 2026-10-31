using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UniRx;
using Zenject;

public class GrazeText : MonoBehaviour
{
    [SerializeField, Header("スコアのUI")]
    CanvasGroup scoreCanvasGroup;

    [SerializeField, Header("グレイズ表示時間")]
    float duration;
    CanvasGroup canvasGroup => GetComponent<CanvasGroup>();
    ModifyTextController modifyTextController => GetComponent<ModifyTextController>();
    Text text => GetComponent<Text>();

    [Inject]
    GrazeNotifier grazeNotifier;

    float timer;
    bool isGrazeView;

    void Start()
    {
        canvasGroup.alpha = 0;
        modifyTextController.enabled = false;

        grazeNotifier.OnGraze
            .Subscribe(x => {
                text.text = "GRAZE";
                if (x > 1)
                {
                    text.text += "×" + x.ToString();
                    canvasGroup.alpha = 1;
                    modifyTextController.enabled = true;
                    timer = 0;
                }
                isGrazeView = true;
            })
            .AddTo(this);
    }

    void Update()
    {
        if(isGrazeView == true) {

            if (timer > duration)
            {
                isGrazeView = false;
                canvasGroup.alpha = 0;
                scoreCanvasGroup.alpha = 1;
                text.text = "";
                modifyTextController.enabled = false;
                timer = 0;
                return;
            }

            canvasGroup.alpha = 1;
            scoreCanvasGroup.alpha = 0;
            modifyTextController.enabled = true;
            timer += Time.deltaTime;


        }
        else
        {
            isGrazeView = false;
            canvasGroup.alpha = 0;
            scoreCanvasGroup.alpha = 1;
            text.text = "";
        }
    }
}
