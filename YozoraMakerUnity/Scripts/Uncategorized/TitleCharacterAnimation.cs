using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using DG.Tweening;

public class TitleCharacterAnimation : MonoBehaviour
{
    

    public float startduration;
    public float startDelay;
    public Ease startEaseType;
    public Vector2 startPosition;
    public Vector2 idlePosition;

    public float endDuration;
    public Ease endEaseType;
    public Vector2 endPosition;

    [SerializeField, Header("遷移ボタン")]
    KeyCode keycode;
    bool isEnd = false;

    SimpleAnimation SimpleAnimation => GetComponent<SimpleAnimation>();

    // Start is called before the first frame update
    void Start()
    {
        this.transform.position = startPosition;
        this.transform.DOMove(idlePosition, startduration).SetEase(startEaseType).SetDelay(startDelay);
    }

    void Update()
    {
        if (isEnd == true) return;

        if (Input.GetKeyDown(keycode))
        {
            SimpleAnimation.Play("NICONICO");
            this.transform.DOKill();
            this.transform.DOMove(endPosition, endDuration).SetEase(endEaseType);
            isEnd = true;
        }
    }

}
