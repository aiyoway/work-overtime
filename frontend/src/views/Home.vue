<template>
    <div class="home">
        <el-row>
            <el-col :span="12">
                <h2 class="primary-text">上月结余：12</h2>
            </el-col>
            <el-col :span="12">
                <h2 class="primary-text">本月累积：22</h2>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="8">
                自定义数量：
                <el-input v-model="hours" placeholder="请输入小时数"></el-input>
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
                <el-button type="primary">提交</el-button>
            </el-col>
        </el-row>
        <el-row>
            <el-col :span="6">
                <el-button>加了2小时</el-button>
            </el-col>
            <el-col :span="6">
                <el-button>加了3小时</el-button>
            </el-col>
            <el-col :span="6">
                <el-button>加了4小时</el-button>
            </el-col>
            <el-col :span="6">
                <el-button>加了8小时</el-button>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import {Button, Row, Col, Input, DatePicker, MessageBox} from 'element-ui'


    export default {
        name: 'home',
        data: function () {
            return {
                hours: 1,
                date: new Date(),
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
                MessageBox.prompt('您还没有注册，马上给自己整一个代号吧', {
                    inputPattern: /^.{1,20}$/,
                    inputErrorMessage: '亲，用户名长度要在1~20个字符内'
                }).then(val => {
                    console.log(val)
                }).catch(() => {
                    console.log('取消了')
                })
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