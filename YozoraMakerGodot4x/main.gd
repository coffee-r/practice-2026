extends Node2D

@export var game_scene: PackedScene
@export var result_scene: PackedScene

func _on_title_game_start():
	# トランジションアニメーション
	await $TransitionUI.transition_in()
	
	# タイトルシーンを削除
	$Title.queue_free()
	
	# ゲームシーンをインスタンス
	var game = game_scene.instantiate()
	add_child(game)
	
	# シグナル接続
	game.game_end.connect(_on_game_end)
	
	# トランジションアニメーション
	await $TransitionUI.transition_out()

func _on_game_end():
	# トランジションアニメーション
	await $TransitionUI.transition_in()
	
	# ゲームシーンを削除
	$Game.queue_free()
	
	# リザルトシーンをインスタンス
	var result = result_scene.instantiate()
	add_child(result)
	
	# シグナル接続
	result.game_retry.connect(_on_game_retry)
	
	# トランジションアニメーション
	await $TransitionUI.transition_out()

func _on_game_retry():
	# トランジションアニメーション
	await $TransitionUI.transition_in()
	
	# リザルトシーンを削除
	$Result.queue_free()
	
	# ゲームシーンをインスタンス
	var game = game_scene.instantiate()
	add_child(game)
	
	# シグナル接続
	game.game_end.connect(_on_game_end)
	
	# トランジションアニメーション
	await $TransitionUI.transition_out()
