<template>
    <div class="home">
        <el-row>
            <el-col :span="12">
                <h2 class="primary-text">上月结余：{{lastMonthSurplus}}</h2>
            </el-col>
            <el-col :span="12">
                <h2 class="primary-text">本月累积：{{thisMonthSurplus}}</h2>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="8">
                自定义数量：
                <el-input v-model="hours" placeholder="请输入小时数" type="number" max="12" min="1"></el-input>
            </el-col>
            <el-col :span="8">
                <el-date-picker
                        v-model="date"
                        align="right"
                        type="date"
                        placeholder="选择加班日期"
                        :picker-options="pickerOptions">
                </el-date-picker>
            </el-col>
            <el-col :span="8">
                <el-button type="primary" @click="overtimes(hours)">提交</el-button>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="6">
                <el-button @click="overtimes(2)">加了2小时</el-button>
            </el-col>
            <el-col :span="6">
                <el-button @click="overtimes(3)">加了3小时</el-button>
            </el-col>
            <el-col :span="6">
                <el-button @click="overtimes(4)">加了4小时</el-button>
            </el-col>
            <el-col :span="6">
                <el-button @click="overtimes(8)">加了8小时</el-button>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import {Button, Row, Col, Input, DatePicker, MessageBox} from 'element-ui'
    import {register, setOvertime, surplus} from "../api";

    export default {
        name: 'home',
        data: function () {
            return {
                hours: 1,
                date: new Date(),
                lastMonthSurplus: 0,
                thisMonthSurplus: 0,
                pickerOptions: {
                    disabledDate(time) {
                        let date = new Date();
                        date.setDate(1);
                        return time.getTime() > Date.now() || time.getTime() < date.getTime() - 3600 * 1000 * 24;
                    },
                    shortcuts: [
                        {
                            text: '昨天',
                            onClick(picker) {
                                const date = new Date();
                                date.setTime(date.getTime() - 3600 * 1000 * 24);
                                picker.$emit('pick', date);
                            }
                        }, {
                            text: '前天',
                            onClick(picker) {
                                const date = new Date();
                                date.setTime(date.getTime() - 3600 * 1000 * 24 * 2);
                                picker.$emit('pick', date);
                            }
                        }]
                }
            }
        },
        components: {
            ElButton: Button,
            ElRow: Row,
            ElCol: Col,
            ElInput: Input,
            ElDatePicker: DatePicker,
            MessageBox
        },
        created() {
            if (!localStorage.getItem('user')) {
                (async () => {
                    try {
                        let val = await MessageBox.prompt('您还没有注册，马上给自己整一个代号吧', {
                            inputPattern: /^.{1,20}$/,
                            inputErrorMessage: '亲，用户名长度要在1~20个字符内'
                        });
                        let res = await register({user: val.value});
                        localStorage.setItem('user', res.data.user);
                        location.reload();
                    } catch (e) {
                        MessageBox.alert(e.response.data.msg).then(() => location.reload());
                        console.error(e.response.data.msg)
                    }
                })()
            } else {
                this.getSurplus();
            }
        },
        methods: {
            async overtimes(hours) {
                try {
                    await setOvertime({hours});
                    this.getSurplus();
                } catch (e) {
                    console.error(e)
                }
            },
            async getSurplus() {
                let res = await surplus();
                this.lastMonthSurplus = res.lastMonthSurplus;
                this.thisMonthSurplus = res.thisMonthSurplus
            }
        }
    }
</script>

<style>
    .home {
        width: 800px;
        margin: auto;
        font-family: "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
    }

    .primary-text {
        color: #303133;
        text-align: center;
    }
</style>