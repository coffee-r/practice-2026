using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using UniRx;
using Zenject;
using DG.Tweening;

public class ScoreText : MonoBehaviour
{
    Text text => GetComponent<Text>();

    [Inject]
    ScoreModel scoreModel;

    void Start()
    {
        text.rectTransform.DOLocalMoveY(text.rectTransform.localPosition.y - 60, 0.3f).SetEase(Ease.OutBounce).SetDelay(0.5f);
        text.text = scoreModel.score.Value.ToString("D3");
        scoreModel.OnAdd
            .Subscribe(x => text.text = x.ToString("D3"))
            .AddTo(this);
    }
}
