# Generated by Django 3.2 on 2021-04-23 14:26

from django.db import migrations, models


class Migration(migrations.Migration):

    dependencies = [
        ('auth_user', '0002_auto_20210423_1856'),
    ]

    operations = [
        migrations.AlterField(
            model_name='resetpassword',
            name='token',
            field=models.CharField(max_length=255, unique=True, verbose_name='توکن'),
        ),
    ]
