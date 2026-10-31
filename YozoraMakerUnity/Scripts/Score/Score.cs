using System;
using System.Collections;
using System.Collections.Generic;

public sealed class Score
{
    public int Value { get; private set; }

    public Score()
    {
        Value = 0;
    }

    public Score(int value)
    {
        if(value < 0)
        {
            throw new ArgumentException("score value cannot minus");
        }

        Value = value;
    }

    public Score Add(int add)
    {
        if (add < 0)
        {
            throw new ArgumentException("score add cannot minus");
        }

        return new Score(Value + add);
    }

    public int Rank()
    {
        if (   0 <= Value && Value <  100) return 1;
        if ( 100 <= Value && Value <  300) return 2;
        if ( 300 <= Value && Value <  600) return 3;
        if ( 600 <= Value && Value < 1000) return 4;
        if (1000 <= Value && Value < 1500) return 5;
        if (1500 <= Value && Value < 2000) return 6;
        if (2000 <= Value && Value < 3000) return 7;
        if (3000 <= Value && Value < 4000) return 8;
        if (4000 <= Value && Value < 5000) return 9;
        if (5000 <= Value                ) return 10;

        throw new Exception("no rank from score value");
    }
}
