using UnityEngine;
using UnityEngine.UI;
using UniRx;
using TweetWithScreenShot;
using KanKikuchi.AudioManager;
using UnityEngine.EventSystems;

namespace CoffeeR.SNS
{
    public sealed class TweetOnClick : MonoBehaviour, IPointerEnterHandler, IPointerExitHandler
    {

        private Button button => GetComponent<Button>();
        private RectTransform rect  => GetComponent<RectTransform>();

        [SerializeField, Header("Tweet時のテキスト"), Multiline(10)]
        string tweetMessage;

        void Start()
        {
            button
                .OnClickAsObservable()
                .TakeUntilDestroy(this)
                .ThrottleFirst(System.TimeSpan.FromMilliseconds(1000))
                .Subscribe(x =>
                {
                    StartCoroutine(TweetManager.TweetWithScreenShot(tweetMessage));
                    SEManager.Instance.Play(SEPath.KURAGEKOUBOU_BUTTON79);
                }).AddTo(this);
        }

        public void OnPointerEnter(PointerEventData eventData)
        {
            rect.localScale = new Vector3(1.1f, 1.1f, 1.0f);
            SEManager.Instance.Play(SEPath.KURAGEKOUBOU_BUTTON45);
        }

        public void OnPointerExit(PointerEventData eventData)
        {
            rect.localScale = new Vector3(1.0f, 1.0f, 1.0f);
            
        }

    }
}

