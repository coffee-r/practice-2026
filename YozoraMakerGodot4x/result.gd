extends Node2D

signal game_retry

func _ready():
	await $ResultUI/ResultScoreControl/HBoxResultStarContainer.animate()
	await $ResultUI/ResultScoreControl.animate()
	$ResultCharacterAnimatedSprite2D.animate_in()
	$ResultUI/RetryLabel.animate()

func _input(event):
	if event is InputEventMouseButton && event.is_pressed():
		# タイマー開始
		$GameRetryTimer.start()
		
		# キャラクターアニメーション
		$ResultCharacterAnimatedSprite2D.animate_out()
		
		# 効果音
		$StartSE.play()

func _on_game_retry_timer_timeout():
	# ゲームリトライシグナル発信
	print('シグナル game_retry')
	emit_signal('game_retry')
