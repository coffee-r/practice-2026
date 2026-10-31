using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using Zenject;

public class ResultBackground : MonoBehaviour
{
    [Inject]
    Score score;

    public List<GameObject> haikei;


    // Start is called before the first frame update
    void Start()
    {
        haikei[0].SetActive(score.Value >= 0);
        haikei[1].SetActive(score.Value >= 0);
        haikei[2].SetActive(score.Value >= 0);
        haikei[3].SetActive(score.Value >= 100);
        haikei[4].SetActive(score.Value >= 100);
        haikei[5].SetActive(score.Value >= 100);
        haikei[6].SetActive(score.Value >= 300);
        haikei[7].SetActive(score.Value >= 300);
        haikei[8].SetActive(score.Value >= 300);
        haikei[9].SetActive(score.Value >= 600);
        haikei[10].SetActive(score.Value >= 600);
        haikei[11].SetActive(score.Value >= 600);
        haikei[12].SetActive(score.Value >= 1000);
        haikei[13].SetActive(score.Value >= 1000);
        haikei[14].SetActive(score.Value >= 1000);
    }

    // Update is called once per frame
    void Update()
    {
        
    }
}
