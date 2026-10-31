extends Node2D

signal game_start

func _input(event):
	if event is InputEventMouseButton && event.is_pressed():
		# タイマーを開始
		$GameStartTimer.start()
		
		# キャラクターアニメーション
		$TitleCharacterAnimatedSprite2D.animate_out()
		
		# ゲーム開始SEを再生
		$StartSE.play()

func _on_game_start_timer_timeout():
	# ゲーム開始シグナルを発信
	emit_signal('game_start')
