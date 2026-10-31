using System;
using UniRx;

public sealed class ScoreModel
{
    private Subject<int> addSubject = new Subject<int>();
    public IObservable<int> OnAdd { get { return addSubject; } }
    public Score score;

    public ScoreModel()
    {
        score = new Score();
        addSubject.OnNext(score.Value);
    }

    public ScoreModel(int value)
    {
        score = new Score(value);
        addSubject.OnNext(score.Value);
    }

    public void Add(int add)
    {
        score = score.Add(add);
        addSubject.OnNext(score.Value);
    }

}


