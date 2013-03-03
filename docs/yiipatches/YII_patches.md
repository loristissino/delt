# Patches to the original Yii Framework

I needed to apply some patches to the original Yii framework.

The patches apply to the release 1.1.13.

To obtain the patches I've run:

    diff -r -c frameworkunpatched framework > /var/www/yii/delt/docs/yiipatches/patches.txt
    
To apply the patches, you need to cd to the framework directory, and run:

    patch --dry-run -p1 < /var/www/yii/delt/docs/yiipatches/patches.txt 

If everything seems to be ok, you can take off the `dry-run` option.
