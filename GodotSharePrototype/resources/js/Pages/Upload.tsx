import { ChangeEvent, useState } from "react";
import { Link, Head } from '@inertiajs/react';
import { PageProps } from '@/types';
import axios from 'axios';
import Footer from '@/Components/Footer';

export default function Upload({ auth }: PageProps<{}>) {
    const [fileList, setFileList] = useState<FileList | null>(null);

    const gameId = "1";

    const handleFileChange = (e: ChangeEvent<HTMLInputElement>) => {
        setFileList(e.target.files);
    };

    const WebglUpload = async () => {

        // // アップロード用のURL
        // let uploadUrl = "";

        // // ディレクトリ作成用の一時PUT URLを取得
        // await axios.post(route('api.game.settings.webgl.url.object', gameId))
        //        .then(response => uploadUrl = response.data.url)
        //        .catch(error => {
        //             alert('アップロードに失敗しました。');
        //             return;
        //         });
        
        // // // URLをログ出力
        // // console.log("アップロードURL " + uploadUrl);

        // let fileData = new FormData();
        // fileData.set('index.html', fileList[0], 'index.html');

        // fetch(
        //     uploadUrl,{
        //         method: 'PUT',
        //         body: fileData
        //     }
        // ).then(response => {
        //     console.log('成功');

        // })
        // .catch(error => {
        //     console.log('失敗');
        // })




        // let uploadUrl = "";

        // await axios.post(route('api.game.settings.webgl.url.object', gameId))
        //        .then(response => uploadUrl = response.data.url)
        //        .catch(error => {
        //             alert('アップロードに失敗しました。');
        //             return;
        //         });


        // const xhr = new XMLHttpRequest();
        // xhr.open("PUT", uploadUrl, true); // signature.url
        // xhr.setRequestHeader("Content-Type", fileList[0].type); // returns CORS error
        // xhr.send(fileList[0]);
        
        // console.log("成功");





        // 以下、動作するコード
        let uploadUrl = "";

        await axios.post(route('api.game.settings.webgl.url.object', gameId))
               .then(response => uploadUrl = response.data.url)
               .catch(error => {
                    alert('アップロードに失敗しました。');
                    return;
                });
        
        await fetch(
            uploadUrl,{
                method: 'PUT',
                body: fileList[0],
                headers: {'Content-Type': fileList[0].type}
            }
        ).then(response => {
            console.log('成功1');
        })
        .catch(error => {
            console.log('失敗');
            return;
        })
    }
    

    return (
        <>
            <Head title="Godot Share" />

            {/* ヘッダー */}
            <div className="flex justify-between mt-6 px-8">
                <Link href={route('home')} >Godot Share</Link>
                {auth.user ? (
                    <Link href={route('dashboard')} >{auth.user.name}</Link>
                ) : (
                    <Link href={route('login')} >ログイン</Link>
                )}
            </div>

            {/* アップローダー */}
            <div>
                <input type="file" id="webgl-build-files" name="webgl-build-files" multiple onChange={handleFileChange} />
            </div>
            <button type='button' onClick={WebglUpload}>アップロード</button>

            {/* フッター */}
            <Footer />
        </>
    );
}
