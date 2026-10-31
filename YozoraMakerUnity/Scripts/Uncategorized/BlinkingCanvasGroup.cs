using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using DG.Tweening;

[RequireComponent(typeof(CanvasGroup))]
public class BlinkingCanvasGroup : MonoBehaviour
{
    [SerializeField, Header("点滅間隔(秒)")]
    float _duration;

    [SerializeField, Header("アニメーションタイプ")]
    Ease _ease;

    CanvasGroup canvasGroup => GetComponent<CanvasGroup>();

    bool ispressd = false;

    // Start is called before the first frame update
    void Start()
    {
        canvasGroup.DOFade(0.0f, _duration).SetEase(_ease).SetLoops(-1, LoopType.Yoyo);
    }

    void Update()
    {
        if (ispressd) return;
        if (Input.GetKeyDown(KeyCode.Space))
        {
            ispressd = true;
            canvasGroup.DOKill();
            canvasGroup.DOFade(0.0f, _duration / 8).SetLoops(-1, LoopType.Yoyo);           
        }
    }
}
