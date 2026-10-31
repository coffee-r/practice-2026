using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;
using DG.Tweening;
using UniRx;
using UniRx.Async;
using Zenject;
using KanKikuchi.AudioManager;
using UnityEngine.SceneManagement;

public class ResultSequence : MonoBehaviour
{
    [Inject]
    Score score;
    public Text rankText;
    public Text scoreText;
    public Image scoreImageFirst;
    public Image scoreImageSecond;
    public RectTransform retryUI;    
    public RectTransform rankingUI;
    public RectTransform snsUI;
    public RectTransform resultGroupTransform;
    public SimpleAnimation characterObject;

    public bool isCharacterAnimatable = false;

    [Inject]
    private SceneTransistor sceneLoader;

    // Start is called before the first frame update
    async void Start()
    {
        rankText.text = "";
        scoreText.text = "";
        scoreImageFirst.rectTransform.localScale = new Vector3(0, 1.0f, 1.0f);
        scoreImageSecond.rectTransform.localScale = new Vector3(0, 1.0f, 1.0f);
        characterObject.transform.position = new Vector3(12, -1.0f, 0);


        await AnimeRankText();
        await AnimeScoreText();
        AnimeCharacter();
        await AnimeOtherUI();

        isCharacterAnimatable = true;
    }

    private void Update()
    {
        if (isCharacterAnimatable == false) return;

        if (Input.GetKeyDown(KeyCode.Space))
        {
            isCharacterAnimatable = false;
            characterObject.transform.DOMove(new Vector3(-12, -1.0f, 0), 0.45f).SetEase(Ease.InCubic);
            characterObject.Play("NICONICO");
            AnimeOtherUIEnd();
            retryUI.GetComponent<BlinkingCanvasGroup>().enabled = true;

            SEManager.Instance.Play(SEPath.KURAGEKOUBOU_BUTTON79);
            Invoke("DelayMethod", 0.2f);
        }        
    }

    void DelayMethod()
    {
        sceneLoader.TransistionScene(EnumSceneName.Game);
    }

    async UniTask<UniTaskVoid> AnimeRankText()
    {
        await UniTask.Delay(200);

        var rank = score.Rank();

        for(var i = 1; i <= rank; i++)
        {
            await UniTask.Delay(240);

            rankText.text += "★";

            if (i < 5)
            {
                SEManager.Instance.Play(SEPath.LASER_SHOOT32);
            }
            else
            {
                SEManager.Instance.Play(SEPath.GRAZE);
            }

            if (i == 5)
            {
                rankText.GetComponent<ModifyTextController>().enabled = true;
            }
        }

        return new UniTaskVoid();

    }

    async UniTask<UniTaskVoid> AnimeScoreText()
    {
        await UniTask.Delay(400);

        SEManager.Instance.Play(SEPath.KURAGEKOUBOU_BUTTON80);

        scoreImageFirst.rectTransform.DOScaleX(1.0f, 0.25f).SetEase(Ease.OutCirc);
        await scoreImageSecond.rectTransform.DOScaleX(1.0f, 0.25f).SetEase(Ease.OutCirc).SetDelay(0.1f).ToAwaiter(default);
        await UniTask.Delay(100);

        scoreImageFirst.rectTransform.SetPivotWithKeepingPosition(new Vector2(1.0f, 0.5f));
        scoreImageSecond.rectTransform.SetPivotWithKeepingPosition(new Vector2(1.0f, 0.5f));

        scoreText.text = score.Value.ToString("D3");

        scoreImageSecond.rectTransform.DOScaleX(0, 0.25f).SetEase(Ease.OutCirc);
        await scoreImageFirst.rectTransform.DOScaleX(0, 0.25f).SetDelay(0.1f).SetEase(Ease.OutCirc).ToAwaiter(default);


        return new UniTaskVoid();
    }

    void AnimeCharacter()
    {
        resultGroupTransform.DOLocalMoveY(120, 0.5f).SetEase(Ease.OutCirc);
        
        characterObject.transform.DOMove(new Vector3(0, -1.0f, 0), 1.0f).SetEase(Ease.OutCubic).SetDelay(0.25f);

    }


    async UniTask<UniTaskVoid> AnimeOtherUI()
    {
        await UniTask.Delay(600);

        retryUI.DOLocalMoveY(-200, 0.6f).SetEase(Ease.OutBack);
        rankingUI.DOLocalMoveY(-200, 0.6f).SetEase(Ease.OutBack).SetDelay(0.2f);
        snsUI.DOLocalMoveY(-200, 0.6f).SetEase(Ease.OutBack).SetDelay(0.2f);
        

        return new UniTaskVoid();

    }

    void AnimeOtherUIEnd()
    {
        retryUI.DOKill();
        rankingUI.DOKill();
        snsUI.DOKill();
        retryUI.DOLocalMoveY(-320, 0.2f).SetEase(Ease.OutBack);
        rankingUI.DOLocalMoveY(-320, 0.2f).SetEase(Ease.OutBack);
        snsUI.DOLocalMoveY(-320, 0.2f).SetEase(Ease.OutBack);

    }

}
