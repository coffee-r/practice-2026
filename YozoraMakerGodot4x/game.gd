extends Node2D

signal game_end

func _ready():
	# スコアの初期化
	Score.value = 0

func _on_player_player_death():
	# タイマー開始
	$GameEndTimer.start()

func _on_game_end_timer_timeout():
	# ゲーム終了シグナル発信
	print('シグナル game_end')
	emit_signal('game_end')
