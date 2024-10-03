import { Deal } from "@/types/model/deal"

export interface CategoryDeal {
  // columns
  id: number
  created_at: string | null
  updated_at: string | null
  name: string
  slug: string
  // relations
  deals: Deal[]
}
