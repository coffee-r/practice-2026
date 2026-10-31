using System;
using System.Collections.Generic;
using System.Linq;

public class TroubleSelector
{
    private Random _random = new Random();

    public TroubleType GetRandomTrouble(List<TroubleOccurrenceWeightData> troubles)
    {
        // OccurrenceWeight の合計を求める
        int totalWeight = troubles.Sum(t => t.OccurrenceWeight);

        // 0 から totalWeight - 1 の間でランダムな数を取得
        int rand = _random.Next(totalWeight);

        // 重みで選択
        int cumulative = 0;
        foreach (var trouble in troubles)
        {
            cumulative += trouble.OccurrenceWeight;
            if (rand < cumulative)
            {
                return trouble.TroubleType;
            }
        }

        return default;
    }
}