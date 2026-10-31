using UnityEngine;
using System.Collections;
using System.Collections.Generic;
using Arbor;
using Zenject;
using KanKikuchi.AudioManager;

namespace CoffeeR.Player.Arbor
{
    [AddComponentMenu("")]
    public class Shoot : StateBehaviour
    {

        [SerializeField, Header("プレイヤー発射弾")]
        ObjectBreaker _objectBreaker;

        [SerializeField, Header("発射速度")]
        float _shootSpeed;

        [SerializeField, Header("スコア加算単位")]
        int _addScoreValue;

        [SerializeField, Header("グレイズ距離")]
        float _distanceGlaze;

        [Inject]
        ScoreModel scoreModel;

        [Inject]
        GrazeNotifier grazeNotifier;

        ShootDirector shootDirector => GetComponentInChildren<ShootDirector>();

        Rigidbody2D playerRigidbody2D => GetComponent<Rigidbody2D>();

        List<Transform> bulletTransformList = new List<Transform>();


        Queue<ObjectBreaker> objectBreakerQueue;

        void Awake()
        {
            objectBreakerQueue = new Queue<ObjectBreaker>();
            for(var i = 0; i < 160; i++)
            {
                var obj = Instantiate(_objectBreaker, new Vector2(999, 999), Quaternion.identity);
                obj.gameObject.SetActive(false);
                objectBreakerQueue.Enqueue(obj);
            }
        }

        // Use this for enter state
        public override void OnStateBegin()
        {
            // 発射オブジェクトを生成
            var objectBreaker = objectBreakerQueue.Dequeue();
            objectBreaker.transform.position = new Vector2(transform.position.x, transform.position.y) + shootDirector.Direction;
            objectBreaker.gameObject.SetActive(true);

            // 発射オブジェクトに速度を持たせる
            objectBreaker.GetComponent<Rigidbody2D>().velocity = shootDirector.Direction * _shootSpeed;

            // 発射オブジェクトに回転を持たせる
            //var rotate = new Vector3(0, 0, ) * Mathf.PI;
            objectBreaker.GetComponent<Rigidbody2D>().AddTorque(Random.Range(-10, 10));

            // プレイヤーは逆向きに移動する
            playerRigidbody2D.velocity = -shootDirector.Direction * _shootSpeed;

           
            // グレイズスコア計算
            var multiValue = 0;
            foreach(var bulletTransform in bulletTransformList)
            {
                if((transform.position - bulletTransform.position).magnitude < _distanceGlaze)
                {
                    multiValue++;
                }
            }

            // スコア加算
            scoreModel.Add(_addScoreValue + bulletTransformList.Count * multiValue);

            // グレイズ通知
            grazeNotifier.Notice(multiValue);

            // グレイズ対象追加
            bulletTransformList.Add(objectBreaker.transform);

            SEManager.Instance.Play(SEPath.LASER_SHOOT32);

            if(multiValue > 0)
            {
                SEManager.Instance.Play(SEPath.GRAZE);
            }

        }

    }

}

