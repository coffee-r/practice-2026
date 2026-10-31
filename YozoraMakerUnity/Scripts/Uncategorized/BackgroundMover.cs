using System.Collections;
using System.Collections.Generic;
using UnityEngine;

/// <summary>
/// 背景を動かすコンポーネント 
/// </summary>
public class BackgroundMover : MonoBehaviour
{
    [SerializeField, Header("x方向の閾値(絶対値)")]
    float x;

    [SerializeField, Header("移動速度")]
    float speed;


    // Start is called before the first frame update
    void Start()
    {
        
    }

    void Update()
    {
        this.transform.position += Vector3.right *  Time.deltaTime * speed;

        if(this.transform.position.x > x)
        {
            this.transform.position = new Vector2(-x * 2, this.transform.position.y);
        }
    }
}
