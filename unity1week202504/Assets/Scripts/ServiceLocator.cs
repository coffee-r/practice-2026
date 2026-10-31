using UnityEngine;
using System;
using System.Collections.Generic;
using System.Linq;

/// <summary>
/// 型（と任意のキー）に対してインスタンスを登録・取得・解除できる軽量なDIコンテナ。
/// シーンをまたいでも利用可能なシングルトンとして動作し、
/// サービスの依存性解決や共通インスタンスの取得を容易にします。
/// </summary>
public class ServiceLocator : MonoBehaviour
{
    /// <summary>
    /// ServiceLocatorの唯一のインスタンス。
    /// </summary>
    public static ServiceLocator Instance { get; private set; }

    /// <summary>
    /// 型とキーのペアを使ってオブジェクトを管理するコンテナ。
    /// 複数の同じ型のインスタンスを用途別に管理できます。
    /// </summary>
    private Dictionary<(Type, string), object> container = new();

    /// <summary>
    /// シーンロード時に自身をシングルトンとして確立し、破棄されないように設定します。
    /// </summary>
    void Awake()
    {
        if (Instance != null && Instance != this)
        {
            Destroy(gameObject);
            return;
        }

        Instance = this;
        DontDestroyOnLoad(gameObject);
    }

    /// <summary>
    /// 指定した型・キーの組み合わせでインスタンスを登録します。
    /// 同じ型・キーの組み合わせがすでに存在する場合はエラーを出します。
    /// </summary>
    /// <typeparam name="T">登録する型</typeparam>
    /// <param name="instance">登録するインスタンス</param>
    /// <param name="key">識別用のキー。省略可能（デフォルトは空文字）</param>
    public void Register<T>(T instance, string key = "")
    {
        var typeKey = (typeof(T), key);
        if (container.ContainsKey(typeKey))
        {
            Debug.LogError($"[ServiceLocator] {typeof(T).Name} (key: {key}) はすでに登録されています");
            return;
        }

        container[typeKey] = instance;
    }

    /// <summary>
    /// 指定した型・キーの組み合わせに対応するインスタンスを解除します。
    /// IDisposableを実装している場合はDisposeも呼び出します。
    /// </summary>
    /// <typeparam name="T">解除する型</typeparam>
    /// <param name="key">識別用のキー。省略可能（デフォルトは空文字）</param>
    public void UnRegister<T>(string key = "")
    {
        var typeKey = (typeof(T), key);
        if (container.TryGetValue(typeKey, out var instance))
        {
            if (instance is IDisposable disposable)
            {
                disposable.Dispose();
            }
            container.Remove(typeKey);
        }
    }

    /// <summary>
    /// 指定した型・キーの組み合わせに対応するインスタンスを取得します。
    /// 見つからない場合は例外をスローします。
    /// </summary>
    /// <typeparam name="T">取得する型</typeparam>
    /// <param name="key">識別用のキー。省略可能（デフォルトは空文字）</param>
    /// <returns>登録されたインスタンス</returns>
    public T Resolve<T>(string key = "")
    {
        var typeKey = (typeof(T), key);
        if (container.TryGetValue(typeKey, out var instance))
        {
            return (T)instance;
        }

        throw new InvalidOperationException($"{typeof(T).Name} (key: {key}) が登録されていません\n現在の登録一覧: {string.Join(", ", container.Keys.Select(k => $"{k.Item1.Name}[{k.Item2}]"))}");
    }
}