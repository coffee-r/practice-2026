import { SiteHeader } from "@/components/site-header";
import { TodoForm } from "@/components/todo-form";
import { Button } from "@/components/ui/button";
import { Table, TableBody, TableCaption, TableCell, TableFooter, TableHead, TableHeader, TableRow } from "@/components/ui/table";
import { auth0 } from "@/lib/auth0";

type Item = {
  id: string;
  name: string;
};

async function getItems(accessToken: string): Promise<Item[]> {
  const res = await fetch(`${process.env.NEXT_PUBLIC_API_BASE_URL}/users/userId/items`, {
    headers: {
      Authorization: `Bearer ${accessToken}`,
      'Content-Type': 'application/json',
    },
    cache: 'no-store', // 最新の情報を取得
  });

  if (!res.ok) {
    console.error('Error fetching items');
    console.error(accessToken);
    return [];
  }

  const data = await res.json();
  return data.items || [];
}

export default async function Dashboard() {
  const session = await auth0.getSession();

  if (!session) {
    return <div>ログインしてください</div>;
  }

  const items = await getItems(session.tokenSet.accessToken);

  return (
    <div>
      <SiteHeader />
      <div className="p-6">
        {/* TodoFormはClient Componentでいい */}
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead className="w-[100px]">ID</TableHead>
              <TableHead>道具名</TableHead>
              <TableHead>操作</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {items.map((item) => (
              <TableRow key={item.id}>
                <TableCell className="font-medium">{item.id}</TableCell>
                <TableCell>{item.name}</TableCell>
                <TableCell className="text-right">
                  {/* 削除ボタンはClient Componentにしていい */}
                  <Button>Close</Button>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
          <TableFooter>
            <TableRow>
              <TableCell colSpan={3}>{items.length}のアイテムがあります。</TableCell>
            </TableRow>
          </TableFooter>
        </Table>
      </div>
    </div>
  );
}