using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class ScoreUIFadeOnPlayerNear : MonoBehaviour
{
    CanvasGroup canvasGroup => GetComponent<CanvasGroup>();
    public Transform playerTransform;
    public Vector2 minVector2;
    public Vector2 maxVector2;

    // Update is called once per frame
    void Update()
    {
        var alpha = canvasGroup.alpha;
        if (minVector2.x <= playerTransform.position.x && playerTransform.position.x <= maxVector2.x)
        {
            if (minVector2.y <= playerTransform.position.y && playerTransform.position.y <= maxVector2.y)
            {

                canvasGroup.alpha -= 0.04f;
                if(canvasGroup.alpha < 0.5f)
                {
                    canvasGroup.alpha = 0.5f;
                }
            }
            else
            {
                canvasGroup.alpha += 0.04f;
                if (canvasGroup.alpha > 1.0f)
                {
                    canvasGroup.alpha = 1.0f;
                }
            }
        }
        else
        {
            canvasGroup.alpha += 0.04f;
            if (canvasGroup.alpha > 1.0f)
            {
                canvasGroup.alpha = 1.0f;
            }
        }
    }
}
