using UnityEngine;
using UnityEngine.UI;
using UniRx;
using naichilab;
using KanKikuchi.AudioManager;
using UnityEngine.EventSystems;
using UnityEngine.SceneManagement;
using Zenject;

namespace CoffeeR.SNS
{
    public class RequestRankUpdateOnClick : MonoBehaviour, IPointerEnterHandler, IPointerExitHandler
    {
        private Button button => GetComponent<Button>();
        private RectTransform rect => GetComponent<RectTransform>();

        public ResultSequence resultSequence;

        [Inject]
        Score score;

        bool isRankingLoaded = false;

        void Start()
        {
            button
                .OnClickAsObservable()
                .Where(x => isRankingLoaded == false)
                .TakeUntilDestroy(this)
                .ThrottleFirst(System.TimeSpan.FromMilliseconds(1000))
                .Subscribe(x => {
                    SEManager.Instance.Play(SEPath.KURAGEKOUBOU_BUTTON79);
                    RankingLoader.Instance.SendScoreAndShowRanking(score.Value);
                }).AddTo(this);


            // イベントにイベントハンドラーを追加
            SceneManager.sceneLoaded += SceneLoaded;
            SceneManager.sceneUnloaded += SceneUnloaded;
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

        void SceneLoaded(Scene nextScene, LoadSceneMode mode)
        {
            if(nextScene.name == "Ranking")
            {
                isRankingLoaded = true;
                resultSequence.isCharacterAnimatable = false;
            }
           
        }

        void SceneUnloaded(Scene thisScene)
        {
            if (thisScene.name == "Ranking")
            {
                isRankingLoaded = false;
                resultSequence.isCharacterAnimatable = true;
            }
        }
    }
}



