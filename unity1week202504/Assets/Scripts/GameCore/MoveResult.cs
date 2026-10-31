
using System.Collections.Generic;

public class MoveResult
{
    public List<string> ResultMessages { get; }
    public List<string> NextReadyMessage { get; }
    public bool IsReachKatanokoya { get; }
    public bool IsReachKitadake { get; }
    public bool IsReachAinodake { get; }
    public bool IsGameOver { get; }

    public MoveResult(List<string> resultMessages, List<string> nextReadyMessage, bool isReachKatanokoya, bool isReachKitadake, bool isReachAinodake,  bool isGameOver)
    {
        ResultMessages = resultMessages;
        NextReadyMessage = nextReadyMessage;
        IsReachKatanokoya = isReachKatanokoya;
        IsReachKitadake = isReachKitadake;
        IsReachAinodake = isReachAinodake;
        IsGameOver = isGameOver;
    }
}
