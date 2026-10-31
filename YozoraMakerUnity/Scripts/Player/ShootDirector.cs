using System.Collections;
using System.Collections.Generic;
using UnityEngine;


public class ShootDirector : MonoBehaviour
{
    [SerializeField]
    [Header("周回スピード(角速度)")]
    float angleSpeed;

    [SerializeField]
    [Header("半径")]
    float radius;

    Transform parentTransform;

    float nowAngle;

    public Vector2 Direction { get; private set; }


    // Start is called before the first frame update
    void Start()
    {
        parentTransform = this.transform.parent;
    }

    // Update is called once per frame
    void Update()
    {
        // 現在の角度を更新
        nowAngle += angleSpeed;

        // 方向を示すオブジェクトの角度を取得
        var nextRotation = Quaternion.Euler(0, 0, nowAngle);

        // 方向を示すオブジェクトの角度を更新
        this.transform.localRotation = nextRotation;

        // 方向を示すオブジェクトの位置を更新
        float rad = nowAngle * Mathf.Deg2Rad;
        this.transform.localPosition = new Vector2((float)Mathf.Cos(rad), (float)Mathf.Sin(rad)) * radius;

        // 外部公開用パラメタ更新
        Direction = this.transform.localPosition.normalized;

    }

    public string DirectionName()
    {

        if (45 <= this.transform.localEulerAngles.z && this.transform.localEulerAngles.z < 135)
        {
            return "TOP";
        }else if (135 <= this.transform.localEulerAngles.z && this.transform.localEulerAngles.z < 225)
        {
            return "LEFT";
        }
        else if (225 <= this.transform.localEulerAngles.z && this.transform.localEulerAngles.z < 315)
        {
            return "DOWN";
        }
        else
        {
            return "RIGHT";
        }
    }
}

