using UnityEngine;
using DG.Tweening;


public class AudioManager : MonoBehaviour
{
    [SerializeField] AudioSource bgmSourceA;
    [SerializeField] AudioSource bgmSourceB;
    [SerializeField] AudioSource seSource;
    [SerializeField] AudioClip[] bgmClips;
    [SerializeField] AudioClip[] seClips;

    AudioSource currentBGMSource;
    AudioSource nextBGMSource;

    void Start()
    {
        currentBGMSource = bgmSourceA;
        nextBGMSource = bgmSourceB;
    }

    public void PlayBGM(string name, float fadeDuration = 0f, bool loop = true)
    {
        var clip = FindClip(bgmClips, name);
        if (clip == null) return;

        if (fadeDuration > 0f)
        {
            currentBGMSource.DOFade(0f, fadeDuration).OnComplete(() =>
            {
                currentBGMSource.clip = clip;
                currentBGMSource.loop = loop;
                currentBGMSource.volume = 0f;
                currentBGMSource.Play();
                currentBGMSource.DOFade(1f, fadeDuration);
            });
        }
        else
        {
            currentBGMSource.clip = clip;
            currentBGMSource.loop = loop;
            currentBGMSource.volume = 1f;
            currentBGMSource.Play();
        }
    }

    public void CrossFadeBGM(string name, float duration = 1f, bool loop = true)
    {
        var clip = FindClip(bgmClips, name);
        if (clip == null) return;

        nextBGMSource.clip = clip;
        nextBGMSource.loop = loop;
        nextBGMSource.volume = 0f;
        nextBGMSource.Play();

        currentBGMSource.DOFade(0f, duration).OnComplete(() => currentBGMSource.Stop());
        nextBGMSource.DOFade(1f, duration);

        // スワップ
        var temp = currentBGMSource;
        currentBGMSource = nextBGMSource;
        nextBGMSource = temp;
    }

    public void StopBGM(float fadeDuration = 0f)
    {
        if (fadeDuration > 0f)
        {
            currentBGMSource.DOFade(0f, fadeDuration).OnComplete(() => currentBGMSource.Stop());
        }
        else
        {
            currentBGMSource.Stop();
        }
    }

    public void PlaySE(string name)
    {
        var clip = FindClip(seClips, name);
        if (clip == null) return;

        seSource.PlayOneShot(clip);
    }

    AudioClip FindClip(AudioClip[] clips, string name)
    {
        foreach (var clip in clips)
        {
            if (clip.name == name)
                return clip;
        }
        Debug.LogWarning($"Audio clip '{name}' not found.");
        return null;
    }
}