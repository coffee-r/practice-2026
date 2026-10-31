
using System.Collections.Generic;

public class ItemUseResult
{
    public List<string> ResultMessages { get; }

    public ItemUseResult(List<string> resultMessages)
    {
        ResultMessages = resultMessages;
    }
}