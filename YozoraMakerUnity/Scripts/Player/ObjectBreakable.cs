using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using Zenject;
using Arbor;
using UniRx.Async;
using DG.Tweening;
using KanKikuchi.AudioManager;

public class ObjectBreakable : MonoBehaviour
{
    [Inject]
    ScoreModel scoreModel;

    [Inject]
    SceneTransistor sceneTransistor;

    public Transform deathAnime0;
    public Transform deathAnime1;

    ArborFSM arbor => GetComponent<ArborFSM>();
    Collider2D collider => GetComponent<Collider2D>();
    ShootDirector shootDirector => GetComponentInChildren<ShootDirector>();

    void OnCollisionEnter2D(Collision2D collision)
    {
        var objectBreaker = collision.gameObject.GetComponent<ObjectBreaker>();
        if (objectBreaker == null) return;
        arbor.enabled = false;
        collider.enabled = false;
        shootDirector.gameObject.SetActive(false);
        Death();

        //Destroy(objectBreaker.gameObject);
        //Destroy(this.gameObject);
    }

    async void Death()
    {
        this.GetComponent<SimpleAnimation>().Play("DEATH");
        SEManager.Instance.Play(SEPath.DEATH);
        foreach (Rigidbody2D obj in UnityEngine.Resources.FindObjectsOfTypeAll(typeof(Rigidbody2D)))
        {
            obj.velocity = Vector3.zero;
            obj.constraints = RigidbodyConstraints2D.FreezeRotation;
        }
        CameraPlay.EarthQuakeShake(0.3f, 30, 2);
        deathAnime0.transform.localScale = new Vector3(10, 6000, 1);
        deathAnime1.transform.localScale = new Vector3(6000, 10, 1);

        deathAnime0.transform.DOScale(new Vector3(0, 6000, 1), 0.25f).SetDelay(0.3f);
        deathAnime1.transform.DOScale(new Vector3(6000, 0, 1), 0.25f).SetDelay(0.3f);
        await UniTask.Delay(1000);
        sceneTransistor.TransistionScene(EnumSceneName.Result, scoreModel.score, default);
    }
}
