import { Head, useForm } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Button } from '@/Components/ui/button';
import { Seller } from '@/types/seller';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/Components/ui/card';
import { Label } from '@/Components/ui/label';
import { Input } from '@/Components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { FormEventHandler, JSXElementConstructor, ReactElement } from 'react';
import BasicLayout from '@/Layouts/BasicLayout';

const SellerEdit = ({ auth, seller }: PageProps<{seller: Seller}>) => {

    const { data, setData, post, processing, errors } = useForm({
        approval_status: seller.approval_status,
    })

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('seller.update', seller.id));
    };

    return (
        <>
            <Head title="出品者詳細" />
            <Card>
                <form onSubmit={submit}>
                    <CardHeader>
                        <CardTitle>出品者の説明</CardTitle>
                        <CardDescription>ここに出品者の説明書きここに出品者の説明書き</CardDescription>
                    </CardHeader>
                    <CardContent className="grid gap-6">
                        <div className="grid gap-2">
                            <Label htmlFor="business-name">出品者名</Label>
                            <Input id="business-name" defaultValue={seller.name} disabled />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="email">メールアドレス</Label>
                            <Input id="email" defaultValue={seller.email} disabled />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="phone">電話番号</Label>
                            <Input id="phone" defaultValue={seller.tel} disabled />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="website">Webサイト</Label>
                            <Input id="website" defaultValue={seller.website_url} disabled />
                        </div>
                        <div className="grid gap-2">
                            <Label htmlFor="status">承認ステータス</Label>
                            <Select name='approval_status' onValueChange={e => setData('approval_status', e)} defaultValue={seller.approval_status}>
                                <SelectTrigger>
                                    <SelectValue placeholder="Select status" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="pending">Pending</SelectItem>
                                    <SelectItem value="approved">Approved</SelectItem>
                                    <SelectItem value="rejected">Rejected</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                    </CardContent>
                    <CardFooter className="flex gap-2">
                        <Button>更新する</Button>
                    </CardFooter>
                </form>
            </Card>
        </>
    );
}

SellerEdit.layout = (page: ReactElement<any, string | JSXElementConstructor<any>>) => <BasicLayout user={page.props.auth.user}>{page}</BasicLayout>;
export default SellerEdit

  
