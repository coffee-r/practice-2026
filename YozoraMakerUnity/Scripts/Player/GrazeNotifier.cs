using System;
using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UniRx;


public sealed class GrazeNotifier
{
    private Subject<int> grazeSubject = new Subject<int>();
    public IObservable<int> OnGraze { get { return grazeSubject; } }

    public void Notice(int grazeCount)
    {
        if(grazeCount < 0){throw new ArgumentException("graze count cannot minus");}
        if(grazeCount < 1){return;}

        grazeSubject.OnNext(grazeCount);
    }

}


