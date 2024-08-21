<template>
    <Page>
        <div class="dasboard-admin space-y-6">
            <h3 class="text-3xl mt-0">Thống kê chung</h3>
            <div class="grid grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="statistic-box">
                    <div class="title text-2xl font-semibold mb-4">
                        Tiền bán
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                            >
                                Hôm nay:
                            </span>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(100000000) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Hôm qua:</span
                            >
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Tháng này:</span
                            >
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Tháng trước:</span
                            >
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                    </div>
                </div>
                <div class="statistic-box">
                    <div class="title text-2xl font-semibold mb-4">
                        Tiền trả
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                            >
                                Hôm nay:
                            </span>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Hôm qua:</span
                            >
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Tháng này:
                            </span>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Tháng trước:
                            </span>
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                    </div>
                </div>
                <div class="statistic-box">
                    <div class="title text-2xl font-semibold mb-4">
                        Tiền hủy
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Hôm nay:</span
                            >
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(50000000) }} vnđ</span
                            >
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                            >
                                Hôm qua:</span
                            >
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ</span
                            >
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Tháng này:</span
                            >
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }} vnđ</span
                            >
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Tháng trước:</span
                            >
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ</span
                            >
                        </div>
                    </div>
                </div>
                <div class="statistic-box">
                    <div class="title text-2xl font-semibold mb-4">
                        Lượng truy cập
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Hôm nay:</span
                            >
                            <span class="number break-all">{{
                                formatCurrencyNumber(100)
                            }}</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Hôm qua:</span
                            >
                            <span class="number break-all !text-red-400">{{
                                formatCurrencyNumber(0)
                            }}</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Tháng này:</span
                            >
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(100) }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <span
                                class="flex-grow-0 flex-shrink-0 "
                                >Tháng trước:</span
                            >
                            <span class="number break-all !text-red-400">{{
                                formatCurrencyNumber(0)
                            }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div class="statistic-box">
                    <div class="title text-2xl font-semibold mb-4">
                        Hoạt động
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Tổng tiền bán:
                            </div>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(100000000) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Tổng tiền trả:
                            </div>
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Tổng tiền hủy:
                            </div>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }} vnđ
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Hóa đơn bán:
                            </div>
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Hóa đơn hủy:
                            </div>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="statistic-box">
                    <div class="title text-2xl font-semibold mb-4">
                        Kho hàng
                    </div>
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Số lượng sản phẩm tồn kho:
                            </div>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(100000000) }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Sản phẩm hết hàng:
                            </div>
                            <span class="number break-all !text-red-400"
                                >{{ formatCurrencyNumber(0) }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <div class="flex-grow-0 flex-shrink-0">
                                Sản phẩm sắp hết hàng:
                            </div>
                            <span class="number break-all"
                                >{{ formatCurrencyNumber(0) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Page>
</template>

<script setup>
import Page from "@/views/layouts/Page";

const formatCurrencyNumber = (number) => {
    return number.toLocaleString();
};
</script>

<style lang="scss" scoped>
.statistic-box {
    background-color: white;
    padding: 24px;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    &.row-flex {
        flex-direction: row;
        justify-content: flex-start;
        color: white;
        gap: 24px;
        background-color: #0c6ab0;
        .number {
            color: white;
        }
    }
    .number {
        color: #004b67;
        font-weight: 600;
        font-size: 26px;
    }
    hr {
        height: 100%;
        width: 2px;
        opacity: 1;
        color: white;
        margin: 0;
        flex-shrink: 0;
        flex-grow: 0;
        border: 1px solid;
    }
}
</style>
