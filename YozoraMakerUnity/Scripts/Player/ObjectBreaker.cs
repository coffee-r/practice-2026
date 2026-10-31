using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using KanKikuchi.AudioManager;

[RequireComponent(typeof(Rigidbody2D))]
public class ObjectBreaker : MonoBehaviour
{
    void OnCollisionEnter2D(Collision2D collision)
    {
        SEManager.Instance.Play(SEPath.KURAGEKOUBOU_BUTTON45);
    }
}
